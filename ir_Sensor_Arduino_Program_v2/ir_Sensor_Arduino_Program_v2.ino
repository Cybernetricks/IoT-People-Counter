#include <MKRWAN.h>

#define in 7    //IR Sensor 1
#define out 8   //IR sensor 2

LoRaModem modem;
String deviceEUI;

String sensorGroup = "2";   //Change for each sensor group
String appEUI = "0000000000000000";
String appKey = "8a5f103ae74463a291cb3853b2dd240d";   //MOVE TO SECURE METHOD WHEN SETTING UP PUBLIC SERVER

int count=0, pos=0;

//Setup function for initial setup
void setup(){
  Serial.begin(9600);

  while (!Serial);

  if (!modem.begin(EU868)) {
    Serial.println("Failed to start module");

    while (1) {}
  };

  Serial.print("Your module version is: ");

  Serial.println(modem.version());

  Serial.print("Your device EUI is: ");

  deviceEUI = modem.deviceEUI();

  Serial.println(deviceEUI);

  int connected = modem.joinOTAA(appEUI, appKey);

  if (!connected) {

    Serial.println("Something went wrong; are you indoor? Move near a window and retry");

    while (1) {}

  }

  modem.minPollInterval(60);

	//IR sensor pins are made as input pins
  pinMode(in, INPUT);	
  pinMode(out, INPUT);
}

//Loop function 
void loop(){  
  if((digitalRead(in))==0){
    delay(20);
    while((digitalRead(in))==0);
      /*
      Arrangement or placing of sensors: while entering the room from outside, sensor1 will be 
      encounterd first and sensor2 will be next.
      pos will tell the position of a person, entering/leaving the room

      If pos=0, default value; No person is entering/leaving the room/hall
      If pos=1, person is entering the room and crossed sensor1 (in)
      If pos=2, person has entered the room after crossing both the sensors
      If pos=3, person is going out of the room and crossed the sensor2 (out)
      If pos=4, person has gone out of the room after crossing both the sensors
      */
      if(pos==0)	
        pos=1;
      else if(pos==3)
        pos=4;
  }

  if(pos==4 && count!=0){
    count--;		//person has left the room, decrement the count
    Serial.println(count);
    modem.beginPacket();
    modem.print("-1 " + sensorGroup);
    int error = modem.endPacket(true);
    packetResult(error);
    pos=0;
  }
  else if(pos==4 && count==0){
    pos=0;
  }

  if((digitalRead(out))==0){
    delay(20);
    while((digitalRead(out))==0);
      if(pos==1)
        pos=2;
      else if(pos==0)
        pos=3;
  }

  if(pos==2){
    count++;		//person has entered the room, increment the count
    Serial.println(count);
    modem.beginPacket();
    modem.print("1 " + sensorGroup);
    int error = modem.endPacket(true);
    packetResult(error);
    pos=0;
  } 
  else if(pos==4 && count!=0){
    count--;		//person has left the room, decrement the count
    Serial.println(count);
    modem.beginPacket();
    modem.print("-1");
    int error = modem.endPacket(true);
    packetResult(error);
    pos=0;
  }
  delay(50);
}

void packetResult(int err) {
  if (err > 0) {

    Serial.println("Message sent correctly!");

  } else {

    Serial.println("Error sending message :(");

    Serial.println("(you may send a limited amount of messages per minute, depending on the signal strength");

    Serial.println("it may vary from 1 message every couple of seconds to 1 message every minute)");

  }
}