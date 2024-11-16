Here is a Markdown file you can use for both Hotspot and PPPoE configurations on MikroTik with the BenFex naming convention.

```markdown
# BenFex MikroTik Configuration Guide

This guide provides step-by-step instructions on configuring a MikroTik router with Hotspot and PPPoE services using the BenFex naming conventions.

---

## 1. Hotspot Configuration

This section walks through configuring the MikroTik router to provide Hotspot services, which allow users to access the internet via a web-based login.

### Step 1: Configure the Hotspot Bridge

1. **Create the Bridge Interface**

   ```bash
   /interface bridge add name=BenFex_Bridge_Hotspot
   ```

2. **Assign an IP Address to the Hotspot Bridge**

   ```bash
   /ip address add address=10.5.50.1/24 interface=BenFex_Bridge_Hotspot
   ```

3. **Create the IP Pool for Hotspot Users**

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

2. **Add User Profiles for Bandwidth Limits**

   ```bash
   /ip hotspot user profile add name=BenFex_UserProfile_Limited rate-limit=2M/2M
   ```

3. **Add Users with Specific Profiles**

   - **User 1**

     ```bash
     /ip hotspot user add name=benfex-user1 password=benfex-pass1 profile=BenFex_UserProfile_Limited
     ```

   - **User 2**

     ```bash
     /ip hotspot user add name=benfex-user2 password=benfex-pass2 profile=BenFex_UserProfile_Limited
     ```

### Step 3: Configure Optional Radius Authentication

1. **Add Radius Server Authentication**

   ```bash
   /radius add address=radius-server-ip secret=radius-secret service=hotspot
   ```

2. **Enable Radius on the Hotspot**

   ```bash
   /ip hotspot profile set use-radius=yes
   ```

---

## 2. PPPoE Configuration

This section guides you through configuring a PPPoE server, typically used by ISPs for secure, authenticated internet access.

### Step 1: Set Up the PPPoE Bridge and Pool

1. **Create the PPPoE Bridge**

   ```bash
   /interface bridge add name=BenFex_Bridge_PPPoE
   ```

2. **Create a PPPoE IP Pool**

   ```bash
   /ip pool add name=BenFex-PPPoE-Pool ranges=172.16.0.2-172.16.3.254
   ```

### Step 2: Configure the PPPoE Server

1. **Create a PPPoE Profile**

   ```bash
   /ppp profile add name=BenFex-PPPoE-Profile local-address=172.16.0.1 remote-address=BenFex-PPPoE-Pool
   ```

2. **Add a PPPoE Server Interface**

   ```bash
   /interface pppoe-server server add service-name=BenFex-PPPoE-Service default-profile=BenFex-PPPoE-Profile disabled=no
   ```

### Step 3: Add PPPoE Users

1. **Add User Accounts for PPPoE**

   - **User 1**

     ```bash
     /ppp secret add name=benfex-pppoe1 password=benfex-pass1 service=ppp profile=BenFex-PPPoE-Profile
     ```

   - **User 2**

     ```bash
     /ppp secret add name=benfex-pppoe2 password=benfex-pass2 service=ppp profile=BenFex-PPPoE-Profile
     ```

### Step 4: Configure Bandwidth Limit Profiles

1. **Create a Bandwidth Profile for Specific Limits**

   ```bash
   /queue simple add name=BenFex-PPPoE-Limited target=172.16.0.2/32 max-limit=10M/20M
   ```

2. **Assign Bandwidth Profile to User**

   ```bash
   /ppp secret set benfex-pppoe1 profile=BenFex-PPPoE-Limited
   ```

---

## Testing and Troubleshooting

1. **Connect a Device**
   - Connect a device to the MikroTik router and test each configuration by attempting to access the internet.

2. **Verify IP and Connectivity**
   - Ensure devices receive the correct IP address from the Hotspot or PPPoE server pools.

3. **Authentication**
   - Ensure users can authenticate successfully with the credentials set.

---

## Conclusion

Using this guide, you can set up both Hotspot and PPPoE services on your MikroTik router with the BenFex naming convention for a clean and organized network configuration.
