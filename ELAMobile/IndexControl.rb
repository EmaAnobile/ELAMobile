require 'rubygems'
require 'sinatra'
#require 'data_mapper'

get '/layout' do
  'Hola Mundo estas en index'
end

get '/index' do
  'hola'
 erb :index

end

post '/index' do

@id='Le paso el valorr!!'
erb :index
end
