package kg.apc.perfmon.metrics;

import org.apache.jorphan.logging.LoggingManager;
import org.apache.log.Logger;
import org.hyperic.sigar.NetInterfaceConfig;
import org.hyperic.sigar.NetInterfaceStat;
import org.hyperic.sigar.SigarException;
import org.hyperic.sigar.SigarProxy;

import java.util.Arrays;
import java.util.LinkedList;

/**
 * @author undera
 */
class NetworkIOMetric extends AbstractPerfMonMetric {

    private static final Logger log = LoggingManager.getLoggerForClass();
    public static final byte RX_BYTES = 0;
    public static final byte RX_DROPPED = 1;
    public static final byte RX_ERRORS = 2;
    public static final byte RX_FRAME = 3;
    public static final byte RX_OVERRUNS = 4;
    public static final byte RX_PACKETS = 5;
    public static final byte TX_BYTES = 6;
    public static final byte TX_CARRIER = 7;
    public static final byte TX_COLLISIONS = 8;
    public static final byte TX_DROPPED = 9;
    public static final byte TX_ERRORS = 10;
    public static final byte TX_OVERRUNS = 11;
    public static final byte USED = 12;
    public static final byte SPEED = 13;
    public static final byte TX_PACKETS = 14;
    public static final String[] types = {"bytesrecv", "rxdrops", "rxerr",
            "rxframe", "rxoverruns", "rx", "bytessent", "txcarrier", "txcollisions", "txdrops",
            "txerr", "txoverruns", "used", "speed", "tx"};
    private int type = -1;
    private final String[] interfaces;
    private double prev = -1;
    private int dividingFactor = 1;

    public NetworkIOMetric(SigarProxy aSigar, MetricParams params) {
        super(aSigar);

        if (params.type.length() == 0) {
            type = RX_BYTES;
        } else {
            type = Arrays.asList(types).indexOf(params.type);
            if (type < 0) {
                throw new IllegalArgumentException("Unknown net io type: " + params.type);
            }
        }
        log.debug("Net metric type: " + type);

        LinkedList list = new LinkedList();
        if (params.iface.length() != 0) {
            list.add(params.iface);
        } else {
            try {
                list.addAll(Arrays.asList(aSigar.getNetInterfaceList()));
            } catch (SigarException ex) {
                log.warn("Can't get network interfaces list", ex);
            }
        }

        interfaces = (String[]) list.toArray(new String[0]);
        dividingFactor = getUnitDividingFactor(params.getUnit());
    }

    static void logAvailableInterfaces(SigarProxy sigar) {
        log.info("*** Logging available network interfaces ***");

        try {
            String[] list = sigar.getNetInterfaceList();
            for (int n = 0; n < list.length; n++) {
                NetInterfaceConfig ifc = sigar.getNetInterfaceConfig(list[n]);
                log.info("Network interface: iface=" + ifc.getName() + " addr=" + ifc.getAddress() + " type=" + ifc.getType());
            }
        } catch (SigarException e) {
            log.debug("Can't get network info", e);
        }
    }

    public void getValue(StringBuffer res) throws SigarException {
        double val = 0;
        double cur;
        int factor = 1;
        for (int n = 0; n < interfaces.length; n++) {
            NetInterfaceStat usage;
            try {
                usage = sigarProxy.getNetInterfaceStat(interfaces[n]);
            } catch (SigarException e) {
                log.error("Failed to get interface stat: " + interfaces[n], e);
                continue;
            }
            switch (type) {
                case RX_BYTES:
                    val += usage.getRxBytes();
                    factor = dividingFactor;
                    break;
                case RX_DROPPED:
                    val += usage.getRxDropped();
                    break;
                case RX_ERRORS:
                    val += usage.getRxErrors();
                    break;
                case RX_FRAME:
                    val += usage.getRxFrame();
                    break;
                case RX_OVERRUNS:
                    val += usage.getRxOverruns();
                    break;
                case RX_PACKETS:
                    val += usage.getRxPackets();
                    break;
                case TX_BYTES:
                    val += usage.getTxBytes();
                    factor = dividingFactor;
                    break;
                case TX_CARRIER:
                    val += usage.getTxCarrier();
                    break;
                case TX_COLLISIONS:
                    val += usage.getTxCollisions();
                    break;
                case TX_DROPPED:
                    val += usage.getTxDropped();
                    break;
                case TX_ERRORS:
                    val += usage.getTxErrors();
                    break;
                case TX_OVERRUNS:
                    val += usage.getTxOverruns();
                    break;
                case USED:
                    val = usage.getTxPackets();
                    break;
                case SPEED:
                    val = usage.getSpeed();
                    break;
                case TX_PACKETS:
                    val += usage.getTxPackets();
                    break;
                default:
                    throw new SigarException("Unknown net io type " + type);
            }
        }

        // some post-processing
        switch (type) {
            case SPEED:
                break;
            case USED:
                break;
            default:
                cur = val;
                val = prev > 0 ? cur - prev : 0;
                prev = cur;
        }
        val = val / factor;
        res.append(Double.toString(val));
    }
}
