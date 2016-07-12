require 'rubygems'
require 'sinatra'

get '/' do
  erb :index
end

get '/howmany' do

@valor = params[:botton]
end

post '/howmany' do

@valor = params[:botton]

@elems=[@valor_primero,@valor]


@elems[@num_elem].size

if @valor == "boton_rosa"

return   @valor

else
  if @valor == "boton_rojo"

  return @elems
end
end
end
