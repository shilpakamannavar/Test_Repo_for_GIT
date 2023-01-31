package kg.apc.perfmon.metrics.jmx;

import javax.management.MBeanServerConnection;
import java.lang.management.CompilationMXBean;
import java.lang.management.ManagementFactory;

/**
 * @author undera
 */
class CompilerDataProvider extends AbstractJMXDataProvider {

    public CompilerDataProvider(MBeanServerConnection mBeanServerConn, boolean diff) throws Exception {
        super(mBeanServerConn, diff);
    }

    protected String getMXBeanType() {
        return ManagementFactory.COMPILATION_MXBEAN_NAME;
    }

    protected Class getMXBeanClass() {
        return CompilationMXBean.class;
    }

    protected long getValueFromBean(Object bean) {
        return ((CompilationMXBean) bean).getTotalCompilationTime();
    }
}
