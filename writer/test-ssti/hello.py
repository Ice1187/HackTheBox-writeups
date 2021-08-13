import os
from flask import Flask, render_template

app = Flask(__name__)

@app.route('/<path>', methods=['GET'])
def test(path):
	path =  os.path.join('/var/www/img', path)
	path = '/img/{}'.format(path)
	results = [[11, 'Tester', 'Title Here', 'tags', 'content here', 'status here', 'date here', path]]
	stories = ['Cool', 'Thing', 'Here']
	return render_template('blog-single.html', results=results, stories=stories)

@app.route('/hello')
def hello_world():
	return '<p>Hello, World!</p>'

