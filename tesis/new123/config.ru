require 'sinatra/base'
Dir.glob('./new123/{helpers,controllers}/*.rb').each { |file| require file }

map("/index")   { run UsersController }
map("/howmany") { run Vechiculo }
map("/")        { run ApplicationController }