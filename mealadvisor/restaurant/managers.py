from django.db import models, transaction, connection

from utils import normalize
from mealadvisor.tools import stem_phrase, extract_numbers

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
