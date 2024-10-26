from flask import Flask
from routes import bp
from database import init_db

app = Flask(__name__)

# Initialize the database (if tables need to be created)
init_db()

# Register routes
app.register_blueprint(bp)

if __name__ == '__main__':
    app.run(debug=True)
