# zfs

zfs list -t all
zfs destroy tank1/subvol-102-disk-1
zfs destroy tank1/subvol-102-disk-1 -r

#proxmox

# replicationWithHostCheck.sh
# #!/bin/bash
if wget adkulan.kz -q -O - | grep -oh Вход > /dev/null 2>&1; then
	pve-zsync sync --source 102 --dest pve2:tank1 --verbose --maxsnap 2
else
	echo 'Error'
fi


# /etc/network/interfaces

auto lo
iface lo inet loopback

iface eno1 inet manual

iface eno2 inet manual

iface eno3 inet manual

iface eno4 inet manual

auto vmbr0
iface vmbr0 inet static
	address  89.218.12.148
	netmask  255.255.255.248
	gateway  89.218.12.145
	bridge_ports eno1
	bridge_stp off
	bridge_fd 0

auto vmbr1
iface vmbr1 inet static
	address  10.10.1.1
	netmask  255.255.255.0
	bridge_ports none
	bridge_stp off
	bridge_fd 0

	post-up echo 1 > /proc/sys/net/ipv4/ip_forward
	post-up iptables -t nat -A POSTROUTING -s '10.10.1.0/24' -o vmbr0 -j MASQUERADE
	post-down iptables -t nat -D POSTROUTING -s '10.10.1.0/24' -o vmbr0 -j MASQUERADE
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 2221 -j DNAT --to 10.10.1.5:22
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 2221 -j DNAT --to 10.10.1.5:22
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 2030 -j DNAT --to 10.10.1.5:2030
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 2030 -j DNAT --to 10.10.1.5:2030
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 25 -j DNAT --to 10.10.1.5:25
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 25 -j DNAT --to 10.10.1.5:25
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 110 -j DNAT --to 10.10.1.5:110
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 110 -j DNAT --to 10.10.1.5:110
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 995 -j DNAT --to 10.10.1.5:995
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 995 -j DNAT --to 10.10.1.5:995
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 445 -j DNAT --to 10.10.1.5:445
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 445 -j DNAT --to 10.10.1.5:445
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 8080 -j DNAT --to 10.10.1.5:8080
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 8080 -j DNAT --to 10.10.1.5:8080
  post-up iptables -t nat -A PREROUTING -i vmbr0 -p tcp --dport 143 -j DNAT --to 10.10.1.5:143
	post-down iptables -t nat -D PREROUTING -i vmbr0 -p tcp --dport 143 -j DNAT --to 10.10.1.5:143
