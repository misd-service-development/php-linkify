# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

	config.vm.box = "boxcutter/ubuntu1604"

	config.vm.define "app" do |normal|

		config.vm.synced_folder ".", "/vagrant",  :owner=> 'vagrant', :group=>'users', :mount_options => ['dmode=777', 'fmode=777']

		config.vm.provider "virtualbox" do |vb|
			# Display the VirtualBox GUI when booting the machine
			vb.gui = false

			# Customize the amount of memory on the VM:
			vb.memory = "512"
		end

		config.vm.provision :shell, path: "vagrant/bootstrap.sh"

	end

end
