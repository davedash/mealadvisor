from django.test import TransactionTestCase
from django.test.client import Client


class AjaxTest(TransactionTestCase):
    fixtures = ['production.json']

    def setUp(self):
        """
        We need to be logged in before we start anything.
        """
        super(AjaxTest, self).setUp()
        qsa      = {'username': 'davedash.livejournal.com',
        'password': 'sexy'}
        response = self.client.post('/login', qsa, follow=True)
        # print dir(response) ['__class__', '__contains__', '__delattr__',
        # '__delitem__', '__dict__', '__doc__', '__getattribute__',
        # '__getitem__', '__hash__', '__init__', '__iter__', '__module__',
        # '__new__', '__reduce__', '__reduce_ex__', '__repr__',
        # '__setattr__', '__setitem__', '__str__', '__weakref__',
        # '_charset', '_container', '_convert_to_ascii', '_get_content',
        # '_headers', '_is_string', '_set_content', 'client', 'close',
        # 'content', 'context', 'cookies', 'delete_cookie', 'flush', 'get',
        # 'has_header', 'items', 'next', 'request', 'set_cookie',
        # 'status_code', 'tell', 'template', 'write']

    def test_menuitem_tagging(self):
        """
        http://127.0.0.1:8000/ajax/tag_add_menu_item?menu_item=1869&tag=foo
        """
        response = self.client.post('/ajax/tag_add_menu_item',
        {'menu_item': 294, 'tag': 'blerg'})
        self.assertContains(response, 'blerg')
