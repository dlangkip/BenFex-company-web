# Complete MikroTik and BenFex Integration Guide

## Initial Router Setup

### 1. Reset MikroTik to Factory Settings
1. Connect to your MikroTik router via Winbox
2. Go to System → Reset Configuration
3. Check "No Default Configuration"
4. Click "Reset Configuration"
5. Wait for the router to restart

### 2. Initial Access
1. Connect to the router using MAC address in Winbox
2. Default login: 
   - Username: admin
   - Password: (empty)

### 3. Create New Admin User
```
/user add name=dlang@benfex password=12345678 group=full
```

### 4. Basic Interface Setup
1. Identify and rename your interfaces:
```
/interface ethernet
set [ find default-name=ether1 ] name=WAN
set [ find default-name=ether2 ] name=LAN
```

2. Set up basic IP addressing:
```
/ip address
add address=192.168.1.1/24 interface=LAN
```

3. Configure WAN interface (choose one):
   
   For Dynamic IP (DHCP):
   ```
   /ip dhcp-client
   add interface=WAN disabled=no
   ```
   
   For Static IP:
   ```
   /ip address
   add address=<your-public-ip>/24 interface=WAN
   /ip route
   add gateway=<your-gateway>
   ```

## BenFex Integration Setup

### 1. Configure Network Address Translation (NAT)
```
/ip firewall nat
add chain=srcnat out-interface=WAN action=masquerade
```

### 2. Set up DHCP Server for LAN
```
/ip pool
add name=dhcp-pool ranges=192.168.1.2-192.168.1.254

/ip dhcp-server
add name=dhcp1 interface=LAN address-pool=dhcp-pool
add name=dhcp1 interface=LAN lease-time=1h

/ip dhcp-server network
add address=192.168.1.0/24 gateway=192.168.1.1 dns-server=8.8.8.8
```

### 3. Configure RADIUS for BenFex
```
/radius
add address=192.168.1.2 secret=benfex123 service=hotspot,ppp authentication=yes accounting=yes
```

### 4. Hotspot Setup
1. Create bridge interface:
```
/interface bridge
add name=bridge-hotspot
/interface bridge port
add bridge=bridge-hotspot interface=LAN
```

2. Configure IP pool for hotspot:
```
/ip pool
add name=hs-pool ranges=10.5.50.2-10.5.50.254

/ip address
add address=10.5.50.1/24 interface=bridge-hotspot
```

3. Set up hotspot server:
```
/ip hotspot profile
add name=benfex-profile use-radius=yes
/ip hotspot
add name=hotspot1 interface=bridge-hotspot address-pool=hs-pool profile=benfex-profile
```

### 5. PPPoE Setup
1. Create PPPoE server:
```
/interface pppoe-server server
add authentication=chap,mschap1,mschap2 default-profile=pppoe-profile interface=LAN service-name=BenFex-PPPoE

/ppp profile
add name=pppoe-profile local-address=172.16.0.1 remote-address=pppoe-pool

/ip pool
add name=pppoe-pool ranges=172.16.0.2-172.16.0.254
```

2. Enable RADIUS authentication for PPP:
```
/ppp aaa
set use-radius=yes accounting=yes interim-update=1m
```

## BenFex Configuration

1. Log into your BenFex dashboard

2. Configure RADIUS Settings:
   - Go to Settings → RADIUS
   - NAS IP Address: 192.168.1.1 (Your MikroTik IP)
   - Secret: benfex123 (Same as configured in MikroTik)
   - Click Save

3. Create a Plan:
   - Go to Plans
   - Create a new plan with desired:
     - Name (e.g., "Basic Plan")
     - Price
     - Validity period
     - Bandwidth limits
     - Click Save

4. Create a Test User:
   - Go to Users → Add User
   - Fill in:
     - Username
     - Password
     - Select the plan you created
     - Click Save

## Testing the Setup

1. Test Hotspot:
   - Connect a device to the LAN port
   - Open a browser
   - You should see the hotspot login page
   - Try logging in with the test user credentials

2. Test PPPoE:
   - Create a PPPoE connection on a test computer
   - Use the test user credentials
   - Verify connection success

## Troubleshooting Tips

1. Check RADIUS connectivity:
```
/radius monitor 0
```

2. View active users:
```
/ip hotspot active print
/ppp active print
```

3. Check system logs:
```
/log print
```

## Important Notes

- Replace 192.168.1.2 with your actual BenFex server IP
- Adjust IP ranges and addresses according to your network plan
- The RADIUS secret (benfex123) should be changed to something secure
- Make sure to secure your MikroTik management access
- Regular backups of both MikroTik and BenFex configurations are recommended

Would you like me to explain any part of this setup in more detail or proceed with a specific section of the configuration?
