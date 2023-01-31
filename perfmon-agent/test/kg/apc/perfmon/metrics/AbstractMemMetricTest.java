/*
 * Copyright 2013 undera.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package kg.apc.perfmon.metrics;

import junit.framework.Test;
import junit.framework.TestCase;
import junit.framework.TestSuite;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarProxy;
import org.hyperic.sigar.SigarProxyCache;

/**
 * @author undera
 */
public class AbstractMemMetricTest extends TestCase {

    public AbstractMemMetricTest(String testName) {
        super(testName);
    }

    public static Test suite() {
        TestSuite suite = new TestSuite(AbstractMemMetricTest.class);
        return suite;
    }

    protected void setUp() throws Exception {
        super.setUp();
    }

    protected void tearDown() throws Exception {
        super.tearDown();
    }

    /**
     * Test of getMetric method, of class AbstractMemMetric.
     */
    public void testGetMetric() {
        System.out.println("getMetric");
        StringBuffer res = new StringBuffer();
        SigarProxy sigar = SigarProxyCache.newInstance(new Sigar(), 500);
        MetricParamsSigar params = new MetricParamsSigar(sigar);
        AbstractMemMetric result = AbstractMemMetric.getMetric(sigar, params);
        assertEquals(params, result.params);
    }
}
