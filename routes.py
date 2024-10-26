from flask import Blueprint, request, jsonify
from database import SessionLocal
from models import Giver, Receiver
from ai_matching import find_best_match, parse_location
from geopy.distance import geodesic

bp = Blueprint('main', __name__)

# Route to add a Giver
@bp.route('/giver', methods=['POST'])
def add_giver():
    data = request.json
    required_fields = ['location', 'food_item', 'category', 'food_type', 'dietary_type', 'quantity', 'cooking_time', 'best_before']

    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields for Giver"}), 400

    with SessionLocal() as session:
        giver = Giver(**data)
        session.add(giver)
        session.commit()

    return jsonify({"status": "Giver added successfully"}), 201

# Route to retrieve all Givers
@bp.route('/givers', methods=['GET'])
def get_givers():
    with SessionLocal() as session:
        givers = session.query(Giver).all()
        return jsonify([giver.__dict__ for giver in givers]), 200

# Route to retrieve a specific Giver by ID
@bp.route('/giver/<int:giver_id>', methods=['GET'])
def get_giver(giver_id):
    with SessionLocal() as session:
        giver = session.query(Giver).filter(Giver.id == giver_id).first()
        if giver:
            return jsonify(giver.__dict__), 200
        else:
            return jsonify({"error": "Giver not found"}), 404

# Route to add a Receiver
@bp.route('/receiver', methods=['POST'])
def add_receiver():
    data = request.json
    required_fields = ['location', 'search_radius_km', 'food_item', 'category', 'food_type', 'dietary_type', 'quantity', 'required_by']

    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields for Receiver"}), 400

    with SessionLocal() as session:
        receiver = Receiver(**data)
        session.add(receiver)
        session.commit()

        best_match, constraints_met = find_best_match(session, receiver)

    if best_match:
        distance_km = geodesic(parse_location(best_match.location), parse_location(receiver.location)).km
        return jsonify({
            "status": "Match found",
            "giver_id": best_match.id,
            "distance_km": distance_km,
            "constraints_met": constraints_met
        }), 200
    else:
        return jsonify({"status": "No match found"}), 404

# Route to retrieve all Receivers
@bp.route('/receivers', methods=['GET'])
def get_receivers():
    with SessionLocal() as session:
        receivers = session.query(Receiver).all()
        return jsonify([receiver.__dict__ for receiver in receivers]), 200

# Route to retrieve a specific Receiver by ID
@bp.route('/receiver/<int:receiver_id>', methods=['GET'])
def get_receiver(receiver_id):
    with SessionLocal() as session:
        receiver = session.query(Receiver).filter(Receiver.id == receiver_id).first()
        if receiver:
            return jsonify(receiver.__dict__), 200
        else:
            return jsonify({"error": "Receiver not found"}), 404

