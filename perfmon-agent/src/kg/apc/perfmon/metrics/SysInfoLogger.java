package kg.apc.perfmon.metrics;

import org.apache.jorphan.logging.LoggingManager;
import org.apache.log.Logger;
import org.hyperic.sigar.SigarProxy;

/**
 * @author undera
 */
public class SysInfoLogger {

    private static final Logger log = LoggingManager.getLoggerForClass();

    public static void doIt(SigarProxy sigar) {
        MetricParamsSigar.logAvailableProcesses(sigar);
        DiskIOMetric.logAvailableFilesystems(sigar);
        NetworkIOMetric.logAvailableInterfaces(sigar);
        log.info("*** Done logging sysinfo ***");
    }
}
