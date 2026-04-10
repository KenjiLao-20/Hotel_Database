import mysql.connector
from mysql.connector import Error

def test_connection():
    """Test Python to MySQL connection"""
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='hotel_db',
            user='root',
            password=''
        )
        
        if connection.is_connected():
            print("✅ Python connected to MySQL successfully!")
            
            cursor = connection.cursor()
            cursor.execute("SELECT COUNT(*) FROM guests")
            guest_count = cursor.fetchone()[0]
            print(f"📊 Total guests in database: {guest_count}")
            
            cursor.execute("SELECT COUNT(*) FROM bookings")
            booking_count = cursor.fetchone()[0]
            print(f"📊 Total bookings: {booking_count}")
            
            cursor.execute("SELECT COUNT(*) FROM rooms WHERE status = 'Available'")
            available_rooms = cursor.fetchone()[0]
            print(f"📊 Available rooms: {available_rooms}")
            
            return connection
            
    except Error as e:
        print(f"❌ Error connecting to MySQL: {e}")
        return None

def get_all_guests(connection):
    """READ operation in Python"""
    cursor = connection.cursor(dictionary=True)
    cursor.execute("SELECT * FROM guests LIMIT 5")
    guests = cursor.fetchall()
    
    print("\n📋 First 5 Guests:")
    for guest in guests:
        print(f"  - {guest['first_name']} {guest['last_name']} ({guest['email']})")
    
    return guests

def get_active_bookings(connection):
    """READ active bookings with JOIN"""
    cursor = connection.cursor(dictionary=True)
    query = """
        SELECT b.booking_id, g.first_name, g.last_name, r.room_number,
               b.check_in_date, b.check_out_date
        FROM bookings b
        JOIN guests g ON b.guest_id = g.guest_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.booking_status = 'Active'
    """
    cursor.execute(query)
    bookings = cursor.fetchall()
    
    print(f"\n🏨 Active Bookings: {len(bookings)}")
    for booking in bookings:
        print(f"  - Booking #{booking['booking_id']}: {booking['first_name']} {booking['last_name']} - Room {booking['room_number']}")
    
    return bookings

if __name__ == "__main__":
    print("=" * 50)
    print("HOTEL CONCIERGE SYSTEM - PYTHON CONNECTION TEST")
    print("=" * 50)
    
    conn = test_connection()
    
    if conn:
        get_all_guests(conn)
        get_active_bookings(conn)
        conn.close()
        print("\n✅ Connection closed successfully.")