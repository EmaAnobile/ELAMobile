require 'rubygems'
require 'sinatra'

get '/' do

  erb :index
end

get '/howmany' do

@valor = params[:botton]

valor1 = [params[:mensaje]]
	
auto = Vechiculo.new

 	
$variable = auto.detenerse
	
end

post '/howmany' do

@valor = params[:botton]

valor1 = [params[:mensaje]]

	valor1 << @valor
auto = Vechiculo.new

 	
$variable = auto.detenerse
 
if @valor == "boton_rosa"
	
	return $variable
	
else
  if @valor == "boton_rojo"

  return valor1
	  
end
	
	
end
end
