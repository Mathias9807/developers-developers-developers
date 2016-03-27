#!/bin/env python
import json
import time
import cgi
import cgitb
cgitb.enable()

# Print response header, mind the newline
print("Content-type: text/plain\n")

def createProduct(name, price, text1, text2):
    now = int(time.time())
    product = {
        'PRODUCT': {
            'NAME': name, 
            'PRICE': price, 
            'TEXTVAL1': text1, 
            'TEXTVAL2': text2
        }, 'META': {
            'LASTUPDATED': now, 
            'CREATED': now
        }
    }
    return product

f = open('products.json', 'r')
products = json.loads(f.read())

try:
    form = cgi.FieldStorage()
    
    query = form['query'].value

    if query == 'listProducts':
        print(json.dumps(products))

    if query == 'addProduct':
        products.append(json.loads(form['p'].value))
        open('products.json', 'w').write(json.dumps(products, indent=4))

        print(json.dumps(products))

    open('out', 'a').write(form['query'].value)

except Exception as e:
    print(str(e))

