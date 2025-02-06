# === Interface Renaming ===
/interface set [find name=ether1] name=ether1-ISP
/interface set [find name=ether2] name=ether2-LAN
/interface set [find name=ether4] name=ether4-PPPoE

# === User Management ===
/user add name=dlang password=dlang@benfex group=full

# === Bridge Configuration ===
/interface bridge add name=benfex-bridge
/interface bridge port add bridge=benfex-bridge interface=ether2-LAN

# === IP Configuration ===
/ip address add address=192.168.6.1/24 interface=benfex-bridge
/ip dns set servers=8.8.8.8,8.8.4.4

# === DHCP and Pool Setup ===
/ip pool add name=hotspot-pool ranges=192.168.6.2-192.168.6.100
/ip dhcp-server add interface=benfex-bridge address-pool=hotspot-pool disabled=no
/ip dhcp-server network add address=192.168.6.0/24 gateway=192.168.6.1 dns-server=8.8.8.8,8.8.4.4

# === RADIUS Configuration ===
/radius add address=11.6.15.4 secret=15041106 service=hotspot
/radius incoming set accept=yes port=3799

# === Hotspot Configuration ===
/ip hotspot profile add name=hsprof1 hotspot-address=192.168.6.1 dns-name="hotspot.benfex" \
   use-radius=yes radius-accounting=yes radius-interim-update=5m nas-port-type=wireless-802.11
/ip hotspot add name=hotspot1 interface=benfex-bridge address-pool=hotspot-pool \
   profile=hsprof1 disabled=no

# === PPPoE Client Setup ===
/interface pppoe-client add name=pppoe-out1 interface=PPPoE-Client \
   user=benfex@client password=ppoe-pass disabled=no \
   add-default-route=yes use-peer-dns=yes

# === NAT Configuration ===
/ip firewall nat add chain=srcnat out-interface=pppoe-out1 action=masquerade

# === Basic Firewall Rules ===
# Input chain protection
/ip firewall filter add chain=input connection-state=established,related action=accept
/ip firewall filter add chain=input connection-state=invalid action=drop
/ip firewall filter add chain=input protocol=icmp action=accept
/ip firewall filter add chain=input in-interface=ether1-ISP action=drop

# Allow RADIUS Traffic
/ip firewall filter add chain=input protocol=udp src-address=11.6.15.4 \
   dst-port=1812,1813,3799 action=accept comment="Allow RADIUS"

# Forward chain protection
/ip firewall filter add chain=forward connection-state=established,related action=accept
/ip firewall filter add chain=forward connection-state=invalid action=drop
/ip firewall filter add chain=forward protocol=icmp action=accept

# === System Services ===







/interface set [find name=ether1] name=ether1-ISP
/interface set [find name=ether2] name=ether2-LAN
/interface set [find name=ether4] name=PPPoE-Client
/user add name=dlang password=dlang@benfex group=full
/interface bridge add name=benfex-bridge
/interface bridge port add bridge=benfex-bridge interface=ether2-LAN
/ip address add address=192.168.6.1/24 interface=benfex-bridge
/ip dns set servers=8.8.8.8,8.8.4.4
/ip pool add name=hotspot-pool ranges=192.168.6.2-192.168.6.100
/ip dhcp-server add interface=benfex-bridge address-pool=hotspot-pool disabled=no
/ip dhcp-server network add address=192.168.6.0/24 gateway=192.168.6.1 dns-server=8.8.8.8,8.8.4.4
/radius add address=11.6.15.4 secret=15041106 service=hotspot
/radius incoming set accept=yes port=3799
/ip hotspot profile add name=hsprof1 hotspot-address=192.168.6.1 dns-name="hotspot.benfex" use-radius=yes radius-accounting=yes radius-interim-update=5m nas-port-type=wireless-802.11
/ip hotspot add name=hotspot1 interface=benfex-bridge address-pool=hotspot-pool profile=hsprof1 disabled=no
/interface pppoe-client add name=pppoe-out1 interface=PPPoE-Client user=benfex@client password=ppoe-pass disabled=no add-default-route=yes use-peer-dns=yes
/ip firewall nat add chain=srcnat out-interface=pppoe-out1 action=masquerade
/ip firewall filter add chain=input connection-state=established,related action=accept
/ip firewall filter add chain=input connection-state=invalid action=drop
/ip firewall filter add chain=input protocol=icmp action=accept
/ip firewall filter add chain=input in-interface=ether1-ISP action=drop
/ip firewall filter add chain=input protocol=udp src-address=11.6.15.4 dst-port=1812,1813,3799 action=accept comment="Allow RADIUS"
/ip firewall filter add chain=forward connection-state=established,related action=accept
/ip firewall filter add chain=forward connection-state=invalid action=drop
/ip firewall filter add chain=forward protocol=icmp action=accept
/system ntp client set enabled=yes primary-ntp=pool.ntp.org
/radius set timeout=3000 accounting-backup=yes
/system ntp client set enabled=yes primary-ntp=pool.ntp.org

# === Optional: RADIUS Timeout Settings ===
/radius set timeout=3000 accounting-backup=yes
