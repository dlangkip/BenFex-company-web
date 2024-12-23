Here's a guide that outlines the configuration for integrating MikroTik Hotspot and PPPoE with BenFex. This configuration will help set up and manage user accounts, bandwidth limits, and authentication in BenFex to work with your MikroTik router.

```markdown
# BenFex & MikroTik Configuration Guide

This guide provides instructions on configuring a MikroTik router with BenFex for Hotspot and PPPoE services. This integration enables user management, billing, and bandwidth control from BenFex, commonly used by ISPs.

---

## Requirements

- **BenFex** installed and configured (including Radius server for authentication)
- **MikroTik Router** accessible via Winbox or terminal
- **Radius Server** IP and Secret configured in BenFex

---

## 1. Hotspot Configuration

This section covers setting up the MikroTik Hotspot to work with BenFex for user authentication and billing.

### Step 1: Configure the Hotspot Bridge

1. **Create the Hotspot Bridge Interface**

   ```bash
   /interface bridge add name=BenFex_Bridge_Hotspot
   ```

2. **Assign an IP Address to the Hotspot Bridge**

   ```bash
   /ip address add address=10.5.50.1/24 interface=BenFex_Bridge_Hotspot
   ```

3. **Define the IP Pool for Hotspot Users**

   ```bash
   /ip pool add name=BenFex-Hotspot-Pool ranges=10.5.50.2-10.5.50.254
   ```

4. **Set Up the Hotspot Network**

   ```bash
   /ip hotspot network add address=10.5.50.0/24 gateway=10.5.50.1 dns-server=8.8.8.8
   ```

### Step 2: Configure the Hotspot Server

1. **Create the Hotspot Server**

   ```bash
   /ip hotspot add name=BenFex_Hotspot_Server interface=BenFex_Bridge_Hotspot address-pool=BenFex-Hotspot-Pool profile=default
   ```

2. **Enable Radius Authentication for Hotspot**

   ```bash
   /ip hotspot profile set default use-radius=yes
   ```

3. **Add Radius Server for Authentication**

   ```bash
   /radius add address=<Radius_Server_IP> secret=<Radius_Secret> service=hotspot
   ```

4. **Create User Profiles in BenFex**

   In BenFex, create user profiles that match the MikroTik profiles for billing and bandwidth limits. BenFex will manage user creation, billing, and bandwidth control through the Radius server.

---

## 2. PPPoE Configuration

This section covers setting up the MikroTik router as a PPPoE server to allow users to connect to the internet using BenFex for user management and billing.

### Step 1: Set Up the PPPoE Bridge and Pool

1. **Create the PPPoE Bridge Interface**

   ```bash
   /interface bridge add name=BenFex_Bridge_PPPoE
   ```

2. **Create an IP Pool for PPPoE Users**

   ```bash
   /ip pool add name=BenFex-PPPoE-Pool ranges=172.16.0.2-172.16.3.254
   ```

### Step 2: Configure the PPPoE Server

1. **Create a PPPoE Profile**

   ```bash
   /ppp profile add name=BenFex-PPPoE-Profile local-address=172.16.0.1 remote-address=BenFex-PPPoE-Pool
   ```

2. **Add PPPoE Server Interface**

   ```bash
   /interface pppoe-server server add service-name=BenFex-PPPoE-Service default-profile=BenFex-PPPoE-Profile disabled=no
   ```

3. **Enable Radius for PPPoE**

   ```bash
   /ppp aaa set use-radius=yes
   ```

4. **Add Radius Server for PPPoE Authentication**

   ```bash
   /radius add address=<Radius_Server_IP> secret=<Radius_Secret> service=ppp
   ```

5. **Create PPPoE Profiles in BenFex**

   Define PPPoE user profiles in BenFex with appropriate bandwidth limits and billing rates. BenFex will handle user authentication and bandwidth control through the Radius server.

### Step 3: Add PPPoE Users in BenFex

1. **Add Users and Assign Profiles**

   In BenFex, add PPPoE users with their respective usernames, passwords, and assigned profiles to enable them to connect via PPPoE.

---

## 3. BenFex and Radius Integration for User Management

### Adding Radius Server in BenFex

1. **Login to BenFex** and navigate to the **Radius Server** settings.
2. **Add Radius Server** details, including the IP, Secret, and Time-out settings, to connect BenFex to the MikroTik router.

### Adding and Managing Users

- Use BenFex to create, manage, and bill Hotspot and PPPoE users.
- Users will authenticate via the Radius server, and their access will be managed based on the profiles defined in BenFex.

---

## Testing & Troubleshooting

1. **Verify IP and Connectivity**
   - Ensure devices receive correct IP addresses and can connect to the Hotspot or PPPoE server.
  
2. **Test User Authentication**
   - Check that users created in BenFex can log in and have appropriate access based on their profiles.

3. **Monitor Usage and Billing in BenFex**
   - Track user sessions, bandwidth usage, and billing details in BenFex for effective management.

---

## Conclusion

This configuration allows BenFex to manage users, billing, and bandwidth for both Hotspot and PPPoE services on a MikroTik router using Radius authentication.
```

This file explains how to configure the Hotspot and PPPoE services on a MikroTik router while integrating BenFex for user management and billing.
