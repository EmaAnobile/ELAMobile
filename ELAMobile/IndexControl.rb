require 'sinatra'

get '/layout' do
  'Hola Mundo estas en index'
end

get '/index.html' do

vBotton = ''

# Asignamos el grupo correcto
if vBotton_Group == ""
vBotton =  "Rosa"
else
vBotton = "NoFunco"
end

puts vBotton

end
