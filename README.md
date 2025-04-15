# avgFlask
Python + PHP powered AI X bot

## Tech Stack
- **Backend**: Laravel (PHP) for API and application logic.
- **AI/ML**: Flask (Python) for AI/ML tasks.
- **Frontend**: React (JavaScript) for the user interface.
- **Database**: MySQL for data storage.

## Python Setup
1. Navigate to the `python/` folder:
   ```bash
   cd python
   ```
2. Create a virtual environment:
   ```bash
   python -m venv venv
   ```
3. Activate the virtual environment:
   - On Windows:
     ```bash
     venv\Scripts\activate
     ```
   - On macOS/Linux:
     ```bash
     source venv/bin/activate
     ```
4. Install Flask:
   ```bash
   pip install flask
   ```
5. Run the Python server:
   ```bash
   python main.py
   ```

## Laravel-Python Integration
- Laravel calls the Python server via the `/call-python` route.
- Python processes the data and returns a response to Laravel.
