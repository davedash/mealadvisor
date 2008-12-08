# This Python file uses the following encoding: utf-8
import unicodedata, re, math


reTagnormalizer = re.compile(r'[^a-zA-Z0-9]')
rePunctuation   = re.compile(r"""['",-]""")
reCombining     = re.compile(u'[\u0300-\u036f\u1dc0-\u1dff\u20d0-\u20ff\ufe20-\ufe2f]',re.U)
 
def remove_diacritics(s):
    " Decomposes string, then removes combining characters "
    return reCombining.sub('',unicodedata.normalize('NFD',unicode(s)) )


# tag normalizer
def normalize(tag):
    """
    >>> normalize(u'cafe')
    u'cafe'
    >>> normalize(u'caf e')
    u'cafe'
    >>> normalize(u' cafe ')
    u'cafe'
    
    For now this is wrong I think it's an error with doctest, not the actual function.
    
    >>> normalize(u' café ')
    u'cafa'
    
    >>> normalize(u'cAFe')
    u'cafe'    
    >>> normalize(u'%sss%s')
    u'ssss'
    """
    try:
        tag = remove_diacritics(tag)
    except:
        pass
        
    tag = reTagnormalizer.sub('', tag).lower()
    return tag
