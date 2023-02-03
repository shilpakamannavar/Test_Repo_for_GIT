package kg.apc.perfmon.client;

import kg.apc.perfmon.PerfMonMetricGetter;
import org.apache.jorphan.logging.LoggingManager;
import org.apache.log.Logger;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

/**
 * @author undera
 */
class StreamTransport extends AbstractTransport {

    private static final Logger log = LoggingManager.getLoggerForClass();
    private InputStream is;
    private OutputStream os;

    public StreamTransport() throws IOException {
        super();
    }

    public void setStreams(InputStream i, OutputStream o) {
        is = i;
        os = o;
    }

    public String readln() {
        int nlCount = 0;
        int b;
        try {
            while ((b = is.read()) >= 0) {
                pos.write(b);
                if (b == '\n') {
                    nlCount++;
                    return getNextLine(nlCount);
                }
            }
            return "";
        } catch (IOException ex) {
            if (nlCount > 0) {
                log.error("Error reading next line", ex);
            }
            return "";
        }
    }

    public void writeln(String line) throws IOException {
        os.write(line.concat(PerfMonMetricGetter.NEWLINE).getBytes());
    }
}
