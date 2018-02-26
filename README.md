#  Novatel Wireless MIFI 4620 - auto reconnect manager

The script makes internet connection with your MIFI modem more reliable. In case of any connection problem it sends reconnect command to reinitialize the link.

The script is tested on [Novatel Wireless MIFI 4620LE](http://www.nvtl.com/products/mobile-broadband-solutions/mifi-intelligent-mobile-hotspots/mifi-4620le-3g4g-lte-global-intelligent-mobile-hotspot/) modem ([datasheet](docs/Novatel_4620LE.pdf)). The admin panel in my case looks like this:

![Verizon Admin Panel](docs/verizon.png)

## Prerequisities
- Novatel Wireless MIFI 4620 modem
- PC with Linux OS (connected to modem 24/7)

## Installation
- Install dependency packages (for Debian-based):
```
sudo apt-get install git wget php php5-curl
```

- Clone the repository:
```
sudo git clone https://github.com/acidtron/novatel-wireless-reconnect.git /opt/nwr
```

- Add entry to the `/etc/crontab` file:
```
*/5 * * * * root /opt/nwr/inet_reconnect.sh "https://google.com" "http://192.168.1.1" "password" >> /var/log/inet_reconnect.log
```
Actually, it's not desired to be a root to execute the script, so change in your own.

- Apply the cron changes:
```
sudo service cron reload
```

- Profit!


## Contributing
You can change this script according to your modem device. This is just the idea to solve connection drops.

Report bugs, request features, and suggest improvements [on Github](https://github.com/acidtron/novatel-wireless-reconnect/issues).

Or better yet, [open a pull request](https://github.com/acidtron/novatel-wireless-reconnect/compare) with the changes you'd like to see.
