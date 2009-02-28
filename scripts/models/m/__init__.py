from django.db import models

class MenuItemImage(models.Model):
    data      = models.TextField(blank=True)
    md5sum    = models.CharField(max_length=96, blank=True)
    height    = models.IntegerField(null=True, blank=True)
    width     = models.IntegerField(null=True, blank=True)

    class Meta:
        db_table = u'menu_item_image'
