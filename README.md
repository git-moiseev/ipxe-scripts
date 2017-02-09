# PXE scripts for unattended infrastructure deploy
## Steps to do
* Set up TFTP server
* Download <a href="undionly.kpxe">undionly.kpxe</a> and save it to your TFTP server directory.
* Configure your DHCP server to hand out undionly.kpxe as the boot file for your server MAC address. If you are using  <b>ISC dhcpd</b> then you need to edit /etc/dhcpd.conf to contain
```
host node-001 {
                hardware ethernet 00:11:22:33:44:55;
                fixed-address 192.168.1.2;
                next-server X.X.X.X;
                filename "undionly.kpxe"
        };
```
where 
- <strong>X.X.X.X</strong> is the IP address of your TFTP server, 
- <strong>00:11:22:33:44:55</strong> is the MAC address of your new node server
The <a href="undionly.kpxe">undionly.kpxe</a> will look the boot script, as file named <code>script.ipxe</code>, in your TFTP server's root directory, or, if this fails, at <a href="http://pxe.plentystars.com/script.ipxe">http://pxe.plentystars.com/script.ipxe</a>. So, you can download <a href="script.ipxe">script.ipxe</a>, customize it and save it to your own TFPT server.
