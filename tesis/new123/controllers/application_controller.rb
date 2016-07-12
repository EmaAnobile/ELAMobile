class ApplicationController < Sinatra::Base
  helpers ApplicationHelper

  # establece la carpeta de vistas a ../views.
  set :views, File.expand_path('../../views', __FILE__)

  # despliega las páginas 404
  not_found do
    title 'Not Found!'
    erb :not_found
  end
end