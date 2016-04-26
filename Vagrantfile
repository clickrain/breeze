require 'json'
require 'yaml'

VAGRANTFILE_API_VERSION = "2"
confPath = $confPath ||= File.expand_path("~/.breeze/Breeze.yaml")

require File.expand_path(File.dirname(__FILE__) + '/scripts/breeze.rb')

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    Breeze.configure(config, YAML::load(File.read(confPath)))
end
