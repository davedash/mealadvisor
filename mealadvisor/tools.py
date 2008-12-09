from stemmer import PorterStemmer

STOP_WORDS = ( 'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves',
'you', 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his',
'himself', 'she', 'her', 'hers', 'herself', 'it', 'its', 'itself', 'they',
'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom',
'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be',
'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing',
'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while',
'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into',
'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up',
'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then',
'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both',
'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not',
'only', 'own', 'same', 'so', 'than', 'too', 'very')

def stem_phrase(phrase):
    words = phrase.lower().replace('.','').replace("'",'').split()

    # ignore stop words
    words = [ word for word in words if not word in STOP_WORDS ]
    
    p = PorterStemmer()
    
    return [p.stem(word,0,len(word)-1) for word in words]
    
def stem(word):
    p = PorterStemmer()
    
    return p.stem(word,0,len(word)-1)
    

def extract_numbers(phrase):
    import re
    p = re.compile(r'\d+')
    return list(set(p.findall(phrase)))

list_count_values = lambda x: dict((y, x.count(y)) for y in set(x))
