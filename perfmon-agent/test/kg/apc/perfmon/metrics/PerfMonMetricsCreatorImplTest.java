/**
 *
 */
package kg.apc.perfmon.metrics;

import junit.framework.TestCase;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarProxy;
import org.hyperic.sigar.SigarProxyCache;

/**
 * @author hifi
 */
public class PerfMonMetricsCreatorImplTest extends TestCase {

    /**
     * Test method for {@link kg.apc.perfmon.metrics.PerfMonMetricsCreatorImpl#getMetricProvider(java.lang.String, kg.apc.perfmon.metrics.MetricParamsSigar, org.hyperic.sigar.SigarProxy)}.
     */
    public final void testGetMetricProvider() {
        PerfMonMetricsCreatorImpl getter = new PerfMonMetricsCreatorImpl();
        System.out.println("createMetric");
        String metricType = "cpu";
        String metricParams = "idle";
        SigarProxy sigarProxy = SigarProxyCache.newInstance(new Sigar(), 500);
        MetricParamsSigar metricParamsSigar = MetricParamsSigar.createFromString(metricParams, sigarProxy);
        Class expResult = CPUTotalMetric.class;
        AbstractPerfMonMetric result = getter.getMetricProvider(metricType, metricParamsSigar, sigarProxy, false);
        assertEquals(expResult, result.getClass());
    }

    /**
     * Test method for {@link kg.apc.perfmon.metrics.PerfMonMetricsCreatorImpl#getMetricProvider(java.lang.String, kg.apc.perfmon.metrics.MetricParamsSigar, org.hyperic.sigar.SigarProxy)}.
     */
    public final void testGetMetricProviderError() {
        Throwable catched = null;
        PerfMonMetricsCreatorImpl getter = new PerfMonMetricsCreatorImpl();
        System.out.println("createMetric");
        String metricType = "not exist";
        String metricParams = "idle";
        SigarProxy sigarProxy = SigarProxyCache.newInstance(new Sigar(), 500);
        MetricParamsSigar metricParamsSigar = MetricParamsSigar.createFromString(metricParams, sigarProxy);
        try {
            AbstractPerfMonMetric result = getter.getMetricProvider(metricType, metricParamsSigar, sigarProxy, false);
        } catch (Throwable e) {
            catched = e;
        }
        assertNotNull(catched);
        assertEquals(RuntimeException.class, catched.getClass());
    }

    public final void testNoExec() {
        PerfMonMetricsCreatorImpl getter = new PerfMonMetricsCreatorImpl();
        System.out.println("no-exec");
        String metricType = "exec";
        String metricParams = "";
        SigarProxy sigarProxy = SigarProxyCache.newInstance(new Sigar(), 500);
        MetricParamsSigar metricParamsSigar = MetricParamsSigar.createFromString(metricParams, sigarProxy);
        AbstractPerfMonMetric result = getter.getMetricProvider(metricType, metricParamsSigar, sigarProxy, true);
        assertNotNull(result);
        assertEquals(InvalidPerfMonMetric.class, result.getClass());
    }


}
