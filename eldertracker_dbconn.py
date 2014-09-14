#!/usr/bin/python

# Stukje python voor het constant updaten van de
# database ter simulatie
import mysql.connector
import random
import math

def distance_on_unit_sphere(lat1, long1, lat2, long2):

    # Convert latitude and longitude to 
    # spherical coordinates in radians.
    degrees_to_radians = math.pi/180.0
        
    # phi = 90 - latitude
    phi1 = (90.0 - lat1)*degrees_to_radians
    phi2 = (90.0 - lat2)*degrees_to_radians
        
    # theta = longitude
    theta1 = long1*degrees_to_radians
    theta2 = long2*degrees_to_radians
        
    # Compute spherical distance from spherical coordinates.
        
    # For two locations in spherical coordinates 
    # (1, theta, phi) and (1, theta, phi)
    # cosine( arc length ) = 
    #    sin phi sin phi' cos(theta-theta') + cos phi cos phi'
    # distance = rho * arc length
    
    cos = (math.sin(phi1)*math.sin(phi2)*math.cos(theta1 - theta2) + 
           math.cos(phi1)*math.cos(phi2))
    arc = math.acos( cos )

    # Remember to multiply arc by the radius of the earth 
    # in your favorite set of units to get length.
    return arc


conn = mysql.connector.connect(user='root', password='', host='127.0.0.1',database='eldertracker_db')
cursor = conn.cursor()




while True:
	for x in range(2,52):
		
		#Krijg gebruikerslocatie uit database en cast naar floats
		#voor distance_on_unit_sphere
		cursor.execute("select device_latit,device_lng from users where device_id="+str(x))
		for row in cursor:
				user_latit = float(row[0])
				user_lng = float(row[1])
		
		#Krijg geofence locatie uit database
		cursor.execute("select geofence_center_latit, geofence_center_lng,geofence_radius from users where device_id="+str(x))
		for row in cursor:
				geof_latit = float(row[0])
				geof_lng = float(row[1])
				geof_radius = float(row[2])
	
		#Convert afstand naar meters enzo
		if distance_on_unit_sphere(user_lng,user_latit,geof_latit,geof_lng) * 6373000 > geof_radius:
			print "Gebruiker verdwaald"
			cursor.execute("update users set verdwaald='true' where device_id="+str(x))
		
		else:
			print "Gebruiker in geofence"
			cursor.execute("update users set verdwaald='false' where device_id="+str(x))
		
		print distance_on_unit_sphere(user_lng,user_latit,geof_latit,geof_lng) * 6373000
		#print geof_radius
		
		conn.commit()

		
conn.close()

