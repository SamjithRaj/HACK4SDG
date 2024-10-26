from geopy.distance import geodesic
from sqlalchemy.orm import Session
from models import Giver, Receiver

def parse_location(location_str):
    lat, lon = map(float, location_str.split(","))
    return lat, lon

def find_best_match(session: Session, receiver: Receiver):
    candidates = session.query(Giver).all()
    best_match = None
    best_distance = float('inf')
    max_constraints_met = 0

    for giver in candidates:
        giver_location = parse_location(giver.location)
        receiver_location = parse_location(receiver.location)

        # Calculate distance between giver and receiver
        distance = geodesic(giver_location, receiver_location).km

        # Count the constraints met for each giver
        constraints_met = 0
        if distance <= receiver.search_radius_km:
            constraints_met += 1
            if giver.food_type == receiver.food_type:
                constraints_met += 1
            if giver.dietary_type == receiver.dietary_type:
                constraints_met += 1
            if giver.quantity >= receiver.quantity:
                constraints_met += 1

            # Update best match based on constraints met, distance, and best_before date
            if (constraints_met > max_constraints_met) or (
                constraints_met == max_constraints_met and (
                    distance < best_distance or (
                        distance == best_distance and giver.best_before < best_match.best_before
                    )
                )
            ):
                best_match = giver
                best_distance = distance
                max_constraints_met = constraints_met

    return best_match, max_constraints_met
