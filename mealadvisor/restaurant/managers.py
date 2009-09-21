from django.db import models, transaction, connection
from utils import normalize
from tools import stem_phrase, extract_numbers


class TagManager(models.Manager):

    def get_tags_for_user(self, profile, match='', limit = None):

        query = """
        SELECT DISTINCT `normalized_tag` AS tag
        FROM menuitem_tag
        WHERE `user_id` = %s AND `tag` LIKE %s

        UNION

        SELECT DISTINCT `normalized_tag` AS tag
        FROM restaurant_tag
        WHERE `user_id` = %s AND `tag` LIKE %s

        ORDER BY tag
        """

        if limit:
            query += " LIMIT %d" % limit

        cursor = connection.cursor()
        cursor.execute(query, (profile.id, match+'%', profile.id, match+'%'))

        tags = []

        for row in cursor.fetchall():
            tags.append(row[0])

        return tags

    def get_or_create(self, **kwargs):
        tag = None

        if 'tag' in kwargs:
            tag = kwargs.pop('tag')
            kwargs['normalized_tag'] = normalize(tag)

        obj, created = self.get_query_set().get_or_create(**kwargs)

        if tag:
            obj.tag = tag

        return obj, created

    def filter(self, *args, **kwargs):
        if 'tag' in kwargs:
            tag = kwargs.pop('tag')
            kwargs['normalized_tag'] = normalize(tag)

        return self.get_query_set().filter(*args, **kwargs)


class RestaurantManager(models.Manager):

    def rated_or_reviewed_by(self, profile):

        query = """
        SELECT restaurant_id, created_at
        FROM restaurant_note
        WHERE user_id = %d
        UNION
        SELECT restaurant_id, created_at
        FROM restaurant_rating
        WHERE user_id = %d
        ORDER BY created_at DESC
        """ % (profile.id, profile.id, )

        cursor = connection.cursor()
        cursor.execute(query)

        restaurant_ids = [item[0] for item in cursor.fetchall()]
        return self.filter(id__in = restaurant_ids)

    def search(self, phrase, offset=0, max=10):
        # we want to stem the words AND extract any numbers
        words = stem_phrase(phrase) + extract_numbers(phrase)

        num_words = len(words)
        if num_words == 0:
            return []

        # mysql specifc
        # e.g. longhorn steakhouse
        # produces
        # SELECT
        #    DISTINCT restaurant_search_index.RESTAURANT_ID, COUNT(*) AS nb,
        # SUM(restaurant_search_index.WEIGHT) AS total_weight FROM
        # restaurant_search_index
        # WHERE (restaurant_search_index.WORD LIKE 'longhorn'
        # OR restaurant_search_index.WORD LIKE 'steakhous') GROUP BY
        # restaurant_search_index.RESTAURANT_ID ORDER BY nb DESC,
        # total_weight DESC
        # LIMIT 10
        query = """
        SELECT DISTINCT
            `restaurant_search_index`.`restaurant_id`,
            COUNT(*) AS nb,
            SUM(`restaurant_search_index`.`weight`) AS total_weight
        FROM
            `restaurant_search_index`
        WHERE
            (%s)
        GROUP BY
            `restaurant_search_index`.`restaurant_id`
        ORDER BY
            nb DESC,
            total_weight DESC
        LIMIT %%s
        OFFSET %%s
        """ \
        % " OR ".join(['`restaurant_search_index`.`word` LIKE ?'] * num_words)

        query   = query.replace('?', '%s')
        cursor  = connection.cursor()
        results = cursor.execute(query, words + [max, offset])

        restaurants = []
        for row in cursor.fetchall():
            try:
                restaurant        = self.get(pk=row[0])
                restaurant.count  = row[1]
                restaurant.weight = row[2]
                restaurants.append(restaurant)
            except:
                pass

        return restaurants

    def get_tagged(self, tag):
        restaurants = []
        tags = RestaurantTag.objects.select_related('restaurant').filter(
        tag=tag)
        [restaurants.append(tag.restaurant) for tag in tags]
        return restaurants

    def reindex(self):
        [r.reindex() for r in self.all()]


class RandomManager(models.Manager):

    def cheap_random(self, num=1):
        try:
            # select the n max ids
            nthmax = self.all().order_by('-id')[:num][num-1].id
            # choose a random number from 1... nth max
            r = random.randint(1, nthmax)
            # select n elements with ids > random
            return self.filter(id__gte=r)[:num]
        except:
            return self.all()[:num]

    def random(self):
        try:
            return self.all().order_by('?')[0]
        except:
            return None
