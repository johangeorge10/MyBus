# MyBus
pending works: 
DONE->
->use browser storage for storing email | Session ->DONE
->customer.php ->DONE
->booking.php | sucessfull.php ->DONE
->Design booked (table) ->DONE
->save the data from sucessfull.php ->DONE
->clear session.storaage , session variables after sucessfull payment ->DONE
->seat.php update the seatbooked from table booked ->DONE
->BUG with TOTAL PRICE ->DONE
->implement new way to calculate cash *[rethink logic from station to station cost calculation in seat.php and bus_details.php] *[encountering issue with the table businfo and busschedule include new column to store the cost from station to station, increment cost for each stop in bus info] ->DONE
->Generate seatticket for each customer ->DONE
->redirect to checkticket | latest ticket after payment -> DONE
->Refund ->DONE
->Date bug ->DONE
->add name in SignUP ->DONE
->unavailable marking red layout issues on station to station ->DONE
->seat spacing in seat.php ->DONE
->seat booking issues with same bus with diff places -> removed constraints on arrtime ->DONE
->Forgot password -phpmailer ->DONE
->sent mail for booked info -phpmailer ->DONE
->sent mail on refund ->DONE
->IsLoggedIn session storage variable -set ->DONE
->Added edit delete for bus schedule


TO DO->
->BUG FIX in AdminPage , add , edit ,delete bus scheddule,autodelete bus schedule , bugs with active and inactive buses
->Different Schedule for the Same BUS

limitation :

:1 bus 1 schedule/day
:REFRESH required for loading new added bus in buses.php
:loading to maindash after each saving in admin pages.
:deleted bus need 2 times to delete 
:sussy overloaading saving in buses.php when alloted route using routing and saving it as active makes 2 bus info in the table bus info
:Every Bus runs everyday




CURRENT->
->Admin Page