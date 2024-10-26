from sqlalchemy import Column, Integer, String, Float, DateTime
from database import Base

class Giver(Base):
    __tablename__ = "givers"

    id = Column(Integer, primary_key=True, index=True)
    food_item = Column(String(255), nullable=False)  # Specify length
    category = Column(String(100), nullable=False)     # Specify length
    food_type = Column(String(100), nullable=False)    # Specify length
    dietary_type = Column(String(100), nullable=False)  # Specify length
    quantity = Column(Float, nullable=False)
    location = Column(String(50), nullable=False)       # Store as 'latitude,longitude' string
    cooking_time = Column(DateTime, nullable=False)
    best_before = Column(DateTime, nullable=False)

class Receiver(Base):
    __tablename__ = "receivers"

    id = Column(Integer, primary_key=True, index=True)
    food_item = Column(String(255), nullable=False)  # Specify length
    category = Column(String(100), nullable=False)     # Specify length
    food_type = Column(String(100), nullable=False)    # Specify length
    dietary_type = Column(String(100), nullable=False)  # Specify length
    quantity = Column(Float, nullable=False)
    location = Column(String(50), nullable=False)       # Specify length
    search_radius_km = Column(Float, nullable=False)
    required_by = Column(DateTime, nullable=False)
