require 'sinatra'

get '/hi' do
  erb :CH
end

post '/hi' do
  @result = params['orig']
  erb :CH
end
