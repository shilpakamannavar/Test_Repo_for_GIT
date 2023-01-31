package kg.apc.perfmon;

import kg.apc.cmdtools.AbstractCMDTool;
import org.apache.jorphan.logging.LoggingManager;
import org.apache.log.Logger;
import org.apache.log.Priority;

import java.io.IOException;
import java.io.PrintStream;
import java.util.ListIterator;

public class AgentTool extends AbstractCMDTool {

    private static final Logger log = LoggingManager.getLoggerForClass();

    protected int processParams(ListIterator args) throws UnsupportedOperationException, IllegalArgumentException {
        LoggingManager.setPriority(Priority.INFO);
        PerfMonWorker worker;
        try {
            worker = getWorker();
        } catch (IOException ex) {
            log.error("Error", ex);
            return 0;
        }

        while (args.hasNext()) {
            String nextArg = (String) args.next();
            log.debug("Arg: " + nextArg);
            if (nextArg.equalsIgnoreCase("--tcp-port")) {
                if (!args.hasNext()) {
                    throw new IllegalArgumentException("Missing TCP Port no");
                }

                worker.setTCPPort(Integer.parseInt((String) args.next()));
            } else if (nextArg.equals("--loglevel")) {
                args.remove();
                String loglevelStr = (String) args.next();
                LoggingManager.setPriority(loglevelStr);
            } else if (nextArg.equalsIgnoreCase("--interval")) {
                if (!args.hasNext()) {
                    throw new IllegalArgumentException("Missing interval specification");
                }

                worker.setInterval(Long.parseLong((String) args.next()));
            } else if (nextArg.equalsIgnoreCase("--udp-port")) {
                if (!args.hasNext()) {
                    throw new IllegalArgumentException("Missing UDP Port no");
                }

                worker.setUDPPort(Integer.parseInt((String) args.next()));
            } else if (nextArg.equals("--auto-shutdown")) {
                args.remove();
                worker.setAutoShutdown();
            } else if (nextArg.equals("--sysinfo")) {
                args.remove();
                worker.logSysInfo();
            } else if (nextArg.equals("--agent-version")) {
                args.remove();
                worker.logVersion();
            } else if (nextArg.equalsIgnoreCase("--no-exec")) {
                args.remove();
                worker.setNoExec(true);
            } else {
                throw new UnsupportedOperationException("Unrecognized option: " + nextArg);
            }
        }

        try {
            worker.startAcceptingCommands();

            while (!worker.isFinished()) {
                worker.processCommands();
            }
        } catch (IOException e) {
            log.error("Error", e);
            return 0;
        }

        return worker.getExitCode();
    }

    protected void showHelp(PrintStream os) {
        os.println("Options for tool 'PerfMon': "
                + "[ --tcp-port <port no> --udp-port <port no> "
                + "--interval <seconds> --loglevel <debug|info|warn|error>"
                + "--sysinfo --auto-shutdown --no-exec]");
    }

    protected PerfMonWorker getWorker() throws IOException {
        return new PerfMonWorker();
    }
}
