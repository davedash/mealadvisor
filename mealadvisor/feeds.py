from django.contrib.syndication.feeds import FeedDoesNotExist, Feed
from mealadvisor.common.models import Restaurant

class LatestRestaurants(Feed):
    title       = "Freshest Restaurants"
    link        = "/"
    description = "A list of the freshest restaurants posted to Meal Advisor"

    def items(self):
        return Restaurant.objects.order_by('-created_at')[:10]

class MenuItemFeed(Feed, restaurantStrippedTitle):
    """
    e.g. /restaurant/hobees/feed
    """

    #   $restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('stripped_title'));
    #   $this->forward404Unless($restaurant instanceof Restaurant);
    #   
    #   $c = new Criteria();
    #   $c->addDescendingOrderByColumn(MenuItemPeer::CREATED_AT);
    #   $c->add(MenuItemPeer::RESTAURANT_ID, $restaurant->getId());
    #   
    #   $items = MenuItemPeer::doSelect($c);
    #   $feed = sfFeed::newInstance('rss201rev2');
    #   $feed->setTitle('Menu items at ' . $restaurant->__toString());
    #   $feed->setLink('@restaurant?stripped_title=' . $restaurant->getStrippedTitle());
    #   $feed->setDescription('A list of the menu items served at '.$restaurant->__toString());
    # 
    #   $feed->setFeedItemsRouteName('@menu_item');
    #   $feed->setItems($items);
    #   $this->feed = $feed;
