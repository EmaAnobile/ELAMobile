class UsersController < ApplicationController
  get '/' do
    title "Usuarios"
    erb :index
  end

  get '/:number' do
    title "Usuario #{params[:number]}"
    erb :index
  end
end