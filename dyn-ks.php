<?php

$ipdata  = '';
$release = '';

if (!empty($_SERVER['REMOTE_ADDR'])) {
    $ipdata       = json_decode(file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']));
    $tz           = $ipdata->timezone;
}; 
if (empty($tz)){ 
    $tz           = 'GMT';
};

if (!empty($_GET["release"])) {
    $release        = $_GET["release"];
};
      
echo "#
# Release $release    
# Client ipdata $ipdata->country $ipdata->regionName $ipdata->city
timezone $tz
";

echo '
install
text
keyboard us
lang en_US.UTF-8
skipx
firewall --disabled
authconfig --enableshadow --enablemd5
selinux --disabled

network  --device=lo --hostname=localhost.localdomain

zerombr
clearpart --all --initlabel
bootloader --location=mbr --timeout=1 --append=quiet

part /boot --fstype=ext4 --size=512
part swap  --size=1024
part pv.01 --size=1 --grow
volgroup sysvg --pesize=4096 pv.01
logvol / --fstype=ext4 --vgname=sysvg --size=4096 --grow --name=root

# part btrfs.01 --size=4096 --ondisk=sda --grow
# btrfs none --label=rhel7 btrfs.01
# btrfs /    --subvol --name=root LABEL=rhel7
# btrfs /var --subvol --name=var  rhel7
# btrfs /usr --subvol --name=usr  rhel7
# btrfs /opt --subvol --name=opt  rhel7

################################################################
# rootpw is empty by default, so, you have no ssh login guys ...
################################################################
rootpw --iscrypted $1$CiLFiTGL$8uPNrZGRKrIPOo87o1kvK1

user --name=captain --groups=wheel --iscrypted --password="$6$HcoNZDOpfQdveS0M$AOj3tdX0.6Ifqp.tAbA2w4c5m1oqgcM4FmIW.Bs5Bhc7RL3sA5lhUcZ3dXauIPC5KAhefeOGv4yuvn/tSPPAs."
sshkey --username=captain "ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEAsgxLaQKGdQacvRi4VRcVQJoUPyJDGGjjMvyIh9bFaIlMHSQM9/bSyY/iQvaCqYmCb+QXzuaQv4BFUIcs6Dcts97txt8aXYmsol8EKGtB4X991OAhmcodqJOCbDcpGRFMPsq7OIjFrFQgoXNVHi7MknPBFF/Co4ZnQ20rlrI+KNMeQLKXJ+ciUF0x/hCZO95hz/ofDvELvbZD8jznOgQ8z9v5p0mMOMH13ax07a6WabO57/MKVZxdVhRZqbtmNGJmj2WKqPfgbd94DyypZu7gg2dxq0g5pXSHyRbzr07wSE+m4r9jQNKkjyjSB1lIjT3OFxOSxv3zi2Cc3A4N9o7ppw== rsa-key-20150826"

%packages --nobase --ignoremissing 
acpid
ntp
dnsmasq
wget 
openssh-clients
vim 
zip 
unzip 
lsof 
sysstat 
iotop
sudo 
mc 
atop 
bash-completion 
bind-utils 
yum-utils
yum-plugin-security
git
%end

reboot

%post --log=/root/my-post-log
exec < /dev/tty3 > /dev/tty3
chvt 3
echo
echo "################################"
echo "# Running Post Configuration   #"
echo "################################"

echo "
Protocol 2
SyslogFacility AUTHPRIV
PermitEmptyPasswords no
PasswordAuthentication yes
PermitRootLogin yes
#PermitRootLogin without-password
ChallengeResponseAuthentication no
GSSAPIAuthentication no
UseDNS no
UsePAM yes
AcceptEnv LANG LC_CTYPE LC_NUMERIC LC_TIME LC_COLLATE LC_MONETARY LC_MESSAGES
AcceptEnv LC_PAPER LC_NAME LC_ADDRESS LC_TELEPHONE LC_MEASUREMENT
AcceptEnv LC_IDENTIFICATION LC_ALL LANGUAGE
AcceptEnv XMODIFIERS
X11Forwarding yes
Subsystem       sftp    /usr/libexec/openssh/sftp-server
ClientAliveInterval 30
ClientAliveCountMax 5
TCPKeepAlive yes
" > /etc/ssh/sshd_config

echo "%wheel        ALL=(ALL)       NOPASSWD: ALL" > /etc/sudoers.d/wheel-nopasswd

echo "IP     \4{ens192}" >> /etc/issue

curl http://repo.plentystars.com/os/bashrc >> /etc/bashrc

%end

';

?>
