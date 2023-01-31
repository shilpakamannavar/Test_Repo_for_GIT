package kg.apc.perfmon.metrics.jmx;

import javax.management.MBeanServerConnection;
import java.lang.management.GarbageCollectorMXBean;
import java.lang.management.ManagementFactory;

/**
 * @author undera
 */
class GCDataProvider extends AbstractJMXDataProvider {

    public GCDataProvider(MBeanServerConnection mBeanServerConn, boolean diff) throws Exception {
        super(mBeanServerConn, diff);
    }

    protected String getMXBeanType() {
        return ManagementFactory.GARBAGE_COLLECTOR_MXBEAN_DOMAIN_TYPE;
    }

    protected Class getMXBeanClass() {
        return GarbageCollectorMXBean.class;
    }

    protected long getValueFromBean(Object bean) {
        return ((GarbageCollectorMXBean) bean).getCollectionTime();
    }
}
