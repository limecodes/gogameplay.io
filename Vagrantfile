# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  
  config.vm.box = "ubuntu/bionic64"

  config.vm.provider :virtualbox do |v|
    v.name = "gogameplay"
  end

  config.vm.hostname = "offer.gogameplay.local"
  config.hostsupdater.aliases = ["offer.gogameplay.local"]
  config.vm.network "private_network", ip: "192.168.11.12"

  # config.vm.synced_folder ".", "/vagrant", disabled: true
  config.vm.synced_folder ".", "/var/www/html/offer.gogameplay.local", owner: 'www-data', group: 'www-data'

  config.vm.define :gogameplay do |gogameplay|
  end

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "./provisioning/playbook.yml"
  end

end
