from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/process', methods=['POST'])
def process():
    data = request.json  # Receive data from Laravel
    # Example: Perform AI/ML task
    result = {"message": "Processed data", "received": data}
    return jsonify(result)

if __name__ == '__main__':
    app.run(port=5000)  # Run the Python server on port 5000
