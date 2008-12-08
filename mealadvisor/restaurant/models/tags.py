from django.db import models, transaction, connection
from utils import normalize

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
    

