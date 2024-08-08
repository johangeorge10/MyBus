CREATE TABLE buses (
  id INT(11) NOT NULL AUTO_INCREMENT,
  bus_number VARCHAR(10) NOT NULL,
  from_location VARCHAR(50) NOT NULL,
  to_location VARCHAR(50) NOT NULL,
  departure_time TIME NOT NULL,
  arrival_time TIME NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO buses (bus_number, from_location, to_location, departure_time, arrival_time)
VALUES
  ('BUS001', 'New York', 'Boston', '08:00:00', '12:00:00'),
  ('BUS002', 'Boston', 'New York', '10:00:00', '14:00:00'),
  ('BUS003', 'Chicago', 'Los Angeles', '09:00:00', '18:00:00'),
  ('BUS004', 'Los Angeles', 'Chicago', '11:00:00', '20:00:00');