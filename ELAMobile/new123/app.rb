require 'rubygems'
require 'sinatra'

get '/' do

  erb :index
end

get '/howmany' do

@valor = params[:botton]
	valor1 = [params[:mensaje]]
end

post '/howmany' do

@valor = params[:botton]

valor1 = [params[:mensaje]]

	valor1 << @valor

 
if @valor == "boton_rosa"

return   valor1

else
  if @valor == "boton_rojo"

  return valor1
	  
end
end
end
