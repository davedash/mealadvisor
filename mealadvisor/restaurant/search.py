from mealadvisor.restaurant.models import Restaurant, Location


class Search:
    RESTAURANT_BY_NAME            = 1
    LOCATION_IN_PLACE             = 2
    RESTAURANT_BY_NAME_IN_PLACE   = 3 # undone
    LOCATION_NEAR_PLACE           = 4 # undone
    RESTAURANT_BY_NAME_NEAR_PLACE = 5 # undone

    def __init__(self, query):
        self.query       = query
        self.search_type = self.RESTAURANT_BY_NAME
        self.name        = None
        self.result_type = 'Restaurant'

        import re
        r = re.compile(r'(?:(.*)\b(in|near)\b\W*(.*)|(.*))')
        (restaurant_name, in_or_near, place, name) = r.match(query).groups()

        # we are RESTAURANT_BY_NAME if "\bnear[: ]"
        if name != None and name.strip() != '':
            self.name = name.strip()
        else:
            self.place       = place.strip()
            self.result_type = 'Location'

            if restaurant_name != None and restaurant_name.strip() != '':
                self.name  = restaurant_name.strip()

                # this can either be
                # RESTAURANT_BY_NAME_IN_PLACE
                # RESTAURANT_BY_NAME_NEAR_PLACE
                self.search_type = self.RESTAURANT_BY_NAME_IN_PLACE \
                if in_or_near == 'in' else self.RESTAURANT_BY_NAME_NEAR_PLACE

            else:
                self.search_type = self.LOCATION_IN_PLACE \
                if in_or_near == 'in' else self.LOCATION_NEAR_PLACE

    def get_results(self):

        if self.search_type == self.RESTAURANT_BY_NAME:
            return Restaurant.objects.search(self.name)
        elif self.search_type == self.LOCATION_IN_PLACE:
            return Location.objects.anyin(self.place)
        elif self.search_type == self.RESTAURANT_BY_NAME_IN_PLACE:
            return Location.objects.search_in(self.name, self.place)
        elif self.search_type == self.LOCATION_NEAR_PLACE:
            return Location.objects.near(place = self.place)
        elif self.search_type == self.RESTAURANT_BY_NAME_NEAR_PLACE:
            return Location.objects.near(place = self.place,
            phrase = self.name)
