from geopy import geocoders
from django.conf import settings

from xml.dom import minidom

class Location:
    accuracy  = None
    latitude  = None
    longitude = None
    city      = None
    state     = None
    zip       = None
    country   = None
    location  = None
    
    
class Geocoder(geocoders.Google):
    COUNTRY = 1
    STATE   = 2
    CITY    = 4
    ZIP     = 5

    def __init__(self, query):
        geocoders.Google.__init__(self, settings.GOOGLE_API_KEY)
        self.geocode(query)

    def parse_xml(self, page, exactly_one=True):
        """
        Parse a location name, latitude, and longitude from an XML response.
        """
        if not isinstance(page, basestring):
            page = self._decode_page(page)
        try:
            doc = minidom.parseString(page)
        except ExpatError:
            places = []
        else:
            places = doc.getElementsByTagName('Placemark')

        if exactly_one and len(places) != 1:
            raise ValueError("Didn't find exactly one placemark! " \
                             "(Found %d.)" % len(places))

        def parse_place(place):
            location = self._get_first_text(place, ['address', 'name']) or None
            points   = place.getElementsByTagName('Point')
            point    = points and points[0] or None
            coords   = self._get_first_text(point, 'coordinates') or None

            if coords:
                longitude, latitude = [float(f) for f in coords.split(',')[:2]]
            else:
                latitude = longitude = None
                _, (latitude, longitude) = self.geocode(location)
            
            # we differ from the original here... we want country details
            l = Location()
            
            address_details = place.getElementsByTagName('AddressDetails')[0]
            l.accuracy      = int(address_details.attributes['Accuracy'].value)
            
            if l.accuracy >= self.COUNTRY:
                country   = address_details.getElementsByTagName('Country')[0]
                l.country = self._get_first_text(country, 'CountryNameCode') 
                
                if l.accuracy >= self.STATE:
                    state   = country.getElementsByTagName('AdministrativeArea')[0]
                    l.state = self._get_first_text(state, 'AdministrativeAreaName')
                    
                    if l.accuracy >= self.CITY:
                        city   = state.getElementsByTagName('Locality')[0]
                        l.city = self._get_first_text(city, 'LocalityName')
                        
                        if l.accuracy >= self.ZIP:
                            zip   = city.getElementsByTagName('PostalCode')[0]
                            l.zip = self._get_first_text(zip, 'PostalCodeNumber')
                
            l.longitude     = longitude
            l.latitude      = latitude
            self.location   = l
            
            return (location, (latitude, longitude))

        if exactly_one:
            return parse_place(places[0])
        else:
            return (parse_place(place) for place in places)
# g = geocoders.Google('YOUR_API_KEY_HERE')  
# >>> place, (lat, lng) = g.geocode("10900 Euclid Ave in Cleveland")  
# >>> print "%s: %.5f, %.5f" % (place, lat, lng)  
# 10900 Euclid Ave, Cleveland, OH 44106, USA: 41.50489, -81.61027
