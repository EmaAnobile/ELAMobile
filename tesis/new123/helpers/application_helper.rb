# application_helper.rb

module ApplicationHelper
  def title(value = nil)
    @title = value if value
    @title ? "#{@title}" : "Título por defecto"
  end
end