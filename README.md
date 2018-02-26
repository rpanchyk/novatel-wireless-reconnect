#  Novatel Wireless MIFI 4620 - auto reconnect manager
The script makes internet connection with your MIFI modem more reliable. In case of any connection problem it sends reconnect command to reinitialize the link.

The script is tested on [Novatel Wireless MIFI 4620LE](http://www.nvtl.com/products/mobile-broadband-solutions/mifi-intelligent-mobile-hotspots/mifi-4620le-3g4g-lte-global-intelligent-mobile-hotspot/) modem ([datasheet](docs/Novatel_4620LE.pdf)). The admin panel in my case looks like this:

![Verizon Admin Panel](docs/verizon.png)

## Prerequisites
- Novatel Wireless MIFI 4620 modem
- PC with Linux OS (connected to modem 24/7)

## Dependencies
The script uses Shell and Php, so install all required dependency packages.
- for _deb package management_ Linux (Debian-based):
```
sudo apt-get install git wget php php5-curl
```
- for _rpm package management_ Linux (RedHat-based):
```
sudo yum install git wget php php5-curl
```

## Installation
- Clone the repository:
```
sudo git clone https://github.com/acidtron/novatel-wireless-reconnect.git /opt/nwr
```

### Manual usage
To use the script manually just run the command:
```
./inet_reconnect.sh "CHECK_ADDRESS" "MODEM_ADDRESS" "MODEL_PASSWORD"
```
where:
- CHECK_ADDRESS - the URL to external web resource, for ex: https://google.com
- MODEM_ADDRESS - the URL to modem admin panel, for ex: http://192.168.1.1
- MODEL_PASSWORD - modem admin panel password

### Automatic execution
To use the script in automatic mode perform actions described below:
- Add entry to the `/etc/crontab` file:
```
*/5 * * * * root /opt/nwr/inet_reconnect.sh "https://google.com" "http://192.168.1.1" "password" >> /var/log/inet_reconnect.log
```
Actually, it's not desired to be a root to execute the script, so change in your own.

- Apply the cron changes:
```
sudo service cron reload
```

## Contributing
You can change this script according to your modem device. This is just the idea to solve connection drops.

Report bugs, request features, and suggest improvements [on Github](https://github.com/acidtron/novatel-wireless-reconnect/issues).

Or better yet, [open a pull request](https://github.com/acidtron/novatel-wireless-reconnect/compare) with the changes you'd like to see.
