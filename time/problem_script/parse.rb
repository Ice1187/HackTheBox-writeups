require 'java'

Dir[".//classpath/*.jar"].each do |f|
      require f
end

java_import 'com.fasterxml.jackson.databind.ObjectMapper'
java_import 'com.fasterxml.jackson.databind.SerializationFeature'
f = File.read(ARGV[0])
content = f
puts content

mapper = ObjectMapper.new
mapper.enableDefaultTyping()
mapper.configure(SerializationFeature::FAIL_ON_EMPTY_BEANS, false);
obj = mapper.readValue(content, java.lang.Object.java_class) # invokes all the setters
puts "stringified: " + mapper.writeValueAsString(obj)

