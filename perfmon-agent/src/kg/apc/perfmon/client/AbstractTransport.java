package kg.apc.perfmon.client;

import kg.apc.perfmon.PerfMonMetricGetter;
import org.apache.jorphan.logging.LoggingManager;
import org.apache.log.Logger;

import java.io.IOException;
import java.io.PipedInputStream;
import java.io.PipedOutputStream;

/**
 * Class that solved most of communication tasks for Transport interface
 * and only readln() and writeln() methods left to be implemented
 *
 * @author undera
 * @see Transport
 */
public abstract class AbstractTransport implements Transport {

    private static final Logger log = LoggingManager.getLoggerForClass();
    protected final PipedOutputStream pos;
    protected final PipedInputStream pis;
    private String label;

    public AbstractTransport() throws IOException {
        pos = new PipedOutputStream();
        pis = new PipedInputStream(pos); //jdk 1.4 compatibility , 256 * 1024); // FIXME: eliminate magic constrant
    }

    public String[] readMetrics() {
        String str = readln();
        return str.split(PerfMonMetricGetter.TAB);
    }

    public void setInterval(long interval) {
        log.debug("Setting interval to " + interval);
        try {
            writeln("interval:" + interval);
        } catch (IOException ex) {
            log.error("Error setting interval", ex);
        }
    }

    public void shutdownAgent() {
        log.info("Shutting down the agent");
        try {
            writeln("shutdown");
        } catch (IOException ex) {
            log.error("Error shutting down", ex);
        }
    }

    public void startWithMetrics(String[] metricsArray) throws IOException {
        String cmd = "metrics:";
        for (int n = 0; n < metricsArray.length; n++) {
            cmd += metricsArray[n].replace('\t', ' ') + PerfMonMetricGetter.TAB;
        }
        log.debug("Starting with metrics: " + cmd);
        writeln(cmd);
    }

    public void disconnect() {
        log.debug("Disconnecting from " + label);
        try {
            writeln("exit");
        } catch (IOException ex) {
            log.error("Error during exit", ex);
        }
    }

    public boolean test() {
        try {
            writeln("test");
        } catch (IOException ex) {
            log.error("Failed to send command", ex);
            return false;
        }
        return readln().startsWith("Yep");
    }

    /**
     * Method retrieves next line from received bytes sequence
     *
     * @param newlineCount
     * @return next command line
     * @throws IOException
     */
    protected String getNextLine(int newlineCount) throws IOException {
        if (newlineCount == 0) {
            return "";
        }
        StringBuffer str = new StringBuffer();
        int b;
        while (pis.available() > 0) {
            b = pis.read();
            if (b == -1) {
                return "";
            }
            if (b == '\n') {
                newlineCount--;
                if (newlineCount == 0) {
                    log.debug("Read lines: " + str.toString());
                    String[] lines = str.toString().split("\n");
                    // FIXME: this leads to queuing lines so we will have time lag!
                    return lines[lines.length - 1];
                }
            }
            str.append((char) b);
        }
        return "";
    }

    public String getAddressLabel() {
        return label;
    }

    /**
     * @param label the label to set
     */
    public void setAddressLabel(String label) {
        this.label = label;
    }
}
