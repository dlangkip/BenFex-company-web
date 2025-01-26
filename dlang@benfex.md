# Complete BenFex and MikroTik Integration Guide

## Initial Router Setup

### 1. Reset MikroTik Configuration
1. Connect to MikroTik via Winbox
2. System → Reset Configuration
3. Check "No Default Configuration"
4. Click "Reset Configuration"
5. Wait for router restart

### 2. Create Management User
```bash
/user add name=dlang@benfex password=12345678 group=full
```

## Basic Network Configuration

### 1. Set System Identity
```bash
/system identity set name="BenFex-Router"
```

### 2. Configure Interfaces
```bash
# Rename interfaces
/interface set [find name="ether1"] name="ether1-ISP"
/interface set [find name="ether2"] name="ether2-LAN"

# Create bridge interface
/interface bridge add name=benfex-bridge

# Add LAN to bridge
/interface bridge port add bridge=benfex-bridge interface=ether4
/interface bridge port add bridge=benfex-bridge interface=wlan1

continue

### 3. Configure IP Addressing
```bash
# Add IP to bridge
/ip address
add address=192.168.2.1/24 interface=BenFex_Bridge

# Configure WAN (If using DHCP)
/ip dhcp-client
add interface=WAN disabled=no

# Configure DNS
/ip dns
set allow-remote-requests=yes servers=8.8.8.8,8.8.4.4
```

## Hotspot Configuration

### 1. Create IP Pool
```bash
/ip pool
add name=BenFex-Hotspot-Pool ranges=192.168.2.2-192.168.2.200
```

### 2. Configure DHCP Server
```bash
/ip dhcp-server
add address-pool=BenFex-Hotspot-Pool authoritative=yes disabled=no interface=BenFex_Bridge name=BenFex-DHCP lease-time=1h

/ip dhcp-server network
add address=192.168.2.0/24 gateway=192.168.2.1 dns-server=8.8.8.8
```

### 3. Configure Hotspot Server
```bash
# Create hotspot profile
/ip hotspot profile
add name=BenFex-Profile hotspot-address=192.168.2.1 \
    login-by=cookie,http-chap,https,http-pap \
    use-radius=yes

# Create hotspot server
/ip hotspot
add name=BenFex-Hotspot interface=BenFex_Bridge \
    address-pool=BenFex-Hotspot-Pool \
    profile=BenFex-Profile \
    addresses-per-mac=1 \
    idle-timeout=5m
```

## PPPoE Configuration

### 1. Create PPPoE Pool
```bash
/ip pool
add name=BenFex-PPPoE-Pool ranges=172.16.0.2-172.16.0.254
```

### 2. Configure PPPoE Server
```bash
# Create PPPoE profile
/ppp profile
add name=BenFex-PPPoE-Profile local-address=172.16.0.1 \
    remote-address=BenFex-PPPoE-Pool

# Create PPPoE server
/interface pppoe-server server
add service-name=BenFex-PPPoE \
    interface=BenFex_Bridge \
    default-profile=BenFex-PPPoE-Profile \
    authentication=chap,mschap1,mschap2 \
    disabled=no
```

## RADIUS Integration

### 1. Configure RADIUS
```bash
# Add RADIUS server
/radius
add address=192.168.2.2 secret=BenFex123 service=hotspot,ppp timeout=3000ms

# Enable RADIUS for PPP
/ppp aaa
set use-radius=yes accounting=yes interim-update=1m

# Enable RADIUS incoming
/radius incoming
set accept=yes
```

### 2. NAT Configuration
```bash
/ip firewall nat
add chain=srcnat out-interface=WAN action=masquerade
```

## BenFex System Configuration

1. Log into your BenFex dashboard
2. Navigate to Settings → RADIUS
3. Configure RADIUS settings:
   - NAS IP: 192.168.2.1 (MikroTik IP)
   - Secret Key: BenFex123
   - Click Save

## Creating Test Plans and Users

### 1. Create Plans in BenFex
1. Go to Plans → Add Plan
2. Create Basic Plan:
   - Name: BenFex-Basic
   - Price: (as needed)
   - Data Limit: (as needed)
   - Time Limit: (as needed)
   - Bandwidth: (as needed)

### 2. Create Test User
1. Go to Users → Add User
2. Fill in:
   - Username: test@benfex
   - Password: test123
   - Select BenFex-Basic plan
   - Save

## Verification and Testing

### 1. Test RADIUS Connection
```bash
/radius monitor 0
```

### 2. View Active Users
```bash
# Check hotspot users
/ip hotspot active print

# Check PPPoE users
/ppp active print
```

### 3. Test User Access
1. Connect device to LAN port
2. For Hotspot:
   - Open browser
   - Login with test credentials
3. For PPPoE:
   - Create PPPoE connection
   - Use test credentials

## Important Notes

1. Security Considerations:
   - Change default password for dlang@benfex
   - Use strong RADIUS secret
   - Secure management access

2. Backup Configuration:
```bash
/system backup save name=benfex-config
```

3. Troubleshooting Commands:
```bash
# View system logs
/log print

# Check DHCP leases
/ip dhcp-server lease print

# Monitor interface traffic
/interface monitor-traffic BenFex_Bridge
```

## Common Issues and Solutions

1. RADIUS Authentication Fails:
   - Verify RADIUS secret matches in both systems
   - Check IP connectivity between MikroTik and BenFex
   - Ensure correct services enabled in RADIUS configuration

2. Users Can't Connect:
   - Verify bridge configuration
   - Check DHCP server status
   - Confirm IP addressing is correct

3. Bandwidth Limits Not Working:
   - Verify plan configuration in BenFex
   - Check RADIUS attributes
   - Monitor queue usage

